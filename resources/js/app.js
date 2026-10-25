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

document.addEventListener('change', (event) => {
    const input = event.target;

    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    if (input.id === 'profile_photo') {
        const preview = document.querySelector('[data-profile-photo-preview]');
        const initials = document.querySelector('[data-profile-photo-initials]');
        const status = document.querySelector('[data-profile-photo-status]');
        const selectedPhoto = input.files?.[0];

        if (!(preview instanceof HTMLImageElement) || !selectedPhoto) {
            return;
        }

        if (!selectedPhoto.type.startsWith('image/')) {
            if (status instanceof HTMLElement) {
                status.textContent = 'Choose a JPG, PNG, or WebP image.';
            }

            return;
        }

        const previousPreviewUrl = preview.dataset.previewUrl;

        if (previousPreviewUrl) {
            URL.revokeObjectURL(previousPreviewUrl);
        }

        const previewUrl = URL.createObjectURL(selectedPhoto);
        preview.dataset.previewUrl = previewUrl;
        preview.src = previewUrl;
        preview.classList.remove('hidden');
        initials?.classList.add('hidden');
        if (status instanceof HTMLElement) {
            status.textContent = `${selectedPhoto.name} selected. Uploading your new photo…`;
        }

        input.form?.requestSubmit();

        return;
    }

    if (input.id !== 'photo') {
        return;
    }

    const preview = document.querySelector('[data-photo-preview]');
    const status = document.querySelector('[data-photo-preview-status]');

    if (!(preview instanceof HTMLImageElement) || !(status instanceof HTMLElement)) {
        return;
    }

    const selectedPhoto = input.files?.[0];

    if (!selectedPhoto) {
        const originalSource = preview.dataset.originalSrc;
        const previousPreviewUrl = preview.dataset.previewUrl;

        if (previousPreviewUrl) {
            URL.revokeObjectURL(previousPreviewUrl);
            delete preview.dataset.previewUrl;
        }

        if (originalSource) {
            preview.src = originalSource;
            preview.classList.remove('hidden');
            status.textContent = 'Current recipe photo';
        } else {
            preview.removeAttribute('src');
            preview.classList.add('hidden');
            status.textContent = 'Your selected photo will appear here.';
        }

        return;
    }

    if (!selectedPhoto.type.startsWith('image/')) {
        return;
    }

    const previousPreviewUrl = preview.dataset.previewUrl;

    if (previousPreviewUrl) {
        URL.revokeObjectURL(previousPreviewUrl);
    }

    const previewUrl = URL.createObjectURL(selectedPhoto);
    preview.dataset.previewUrl = previewUrl;
    preview.src = previewUrl;
    preview.classList.remove('hidden');
    status.textContent = `${selectedPhoto.name} selected. Save your changes to update the recipe photo.`;
});