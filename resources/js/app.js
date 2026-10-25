document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-recipe-video-play]');

    if (!(button instanceof HTMLButtonElement)) {
        return;
    }

    const container = button.parentElement;
    const video = container?.querySelector('video');
    const errorMessage = container?.querySelector('[data-recipe-video-error]');

    if (!(video instanceof HTMLVideoElement)) {
        return;
    }

    try {
        await video.play();
        button.hidden = true;
        errorMessage?.classList.add('hidden');
        video.addEventListener('pause', () => {
            button.hidden = false;
        }, { once: true });
    } catch {
        if (errorMessage) {
            errorMessage.textContent = 'The recipe video could not be played. Please try again.';
            errorMessage.classList.remove('hidden');
        }
    }
});