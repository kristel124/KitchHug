<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    private const MODEL = 'gemini-flash-lite-latest';

    private const SYSTEM_PROMPT = <<<'PROMPT'
You are Hugsy, a friendly and enthusiastic cooking assistant for KitchHug — a recipe-sharing platform for home cooks.

Your personality:
- Warm, encouraging, and fun — like a knowledgeable friend in the kitchen.
- Use occasional food emojis to keep things lively, but don't overdo it.
- Keep answers concise and practical. Use bullet points or numbered steps when listing ingredients or instructions.

Your capabilities:
- Suggest recipes based on ingredients the user has.
- Explain cooking techniques, methods, and food science.
- Recommend ingredient substitutions and alternatives.
- Help with meal planning and dietary needs (vegetarian, vegan, gluten-free, etc.).
- Advise on fixing cooking mistakes (over-salted, burnt, etc.).
- Provide tips on food storage, prep, and kitchen hacks.
- Share knowledge about Filipino cuisine and other cuisines.

Restrictions:
- ONLY discuss food, cooking, recipes, ingredients, and kitchen-related topics.
- If asked about anything unrelated to cooking or food, politely say: "I'm Hugsy, your cooking assistant — I can only help with food and cooking topics! 🍳 Is there something in the kitchen I can help you with?"
- Never provide medical diagnoses. If someone mentions a health condition, suggest consulting a doctor while offering general dietary information.
- Keep responses under 200 words unless a full recipe is requested.
PROMPT;

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:20'],
            'history.*.role' => ['required', 'in:user,model'],
            'history.*.text' => ['required', 'string', 'max:2000'],
        ]);

        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return response()->json(['reply' => 'Hugsy is not configured yet. Please contact the admin.'], 503);
        }

        // Build conversation turns from history
        $contents = [];
        foreach (array_slice($validated['history'] ?? [], 0, -1) as $turn) {
            $contents[] = [
                'role' => $turn['role'],
                'parts' => [['text' => $turn['text']]],
            ];
        }
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $validated['message']]],
        ];

        $payload = json_encode([
            'system_instruction' => [
                'parts' => [['text' => self::SYSTEM_PROMPT]],
            ],
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 600,
                'temperature' => 0.75,
            ],
        ]);

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.self::MODEL.':generateContent?key='.$apiKey;
        $body = $this->callViaCurl($url, $payload);

        if ($body === null) {
            return response()->json(['reply' => 'Sorry, I couldn\'t get a response right now. Please try again! 🍳'], 200);
        }

        $data = json_decode($body, true);

        if (isset($data['error']['code']) && $data['error']['code'] === 429) {
            return response()->json(['reply' => 'Hugsy is a little overwhelmed right now 😅 The daily quota has been reached. Please try again later!'], 200);
        }

        $reply = $data['candidates'][0]['content']['parts'][0]['text']
            ?? 'Hmm, I didn\'t catch that. Could you try asking again?';

        return response()->json(['reply' => trim($reply)]);
    }

    /**
     * Use system curl.exe (WinSSL/Schannel) instead of PHP's OpenSSL cURL,
     * which cannot reach googleapis.com on this machine.
     */
    private function callViaCurl(string $url, string $payload): ?string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'hugsy_');
        file_put_contents($tmpFile, $payload);

        $cmd = [
            'C:\\Windows\\System32\\curl.exe',
            '--silent',
            '--max-time', '25',
            '-X', 'POST',
            '-H', 'Content-Type: application/json',
            '--data', '@'.$tmpFile,
            $url,
        ];

        $process = proc_open(
            $cmd,
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        if (! is_resource($process)) {
            @unlink($tmpFile);

            return null;
        }

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        @unlink($tmpFile);

        return ($exitCode === 0 && ! empty($output)) ? $output : null;
    }
}
