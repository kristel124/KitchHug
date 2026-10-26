document.addEventListener('play', (event) => {
    const video = event.target;

    if (!(video instanceof HTMLVideoElement) || !video.matches('[data-recipe-video-player]')) {
        return;
    }

    video.muted = false;
    video.volume = 1;
}, true);

const selectCommunityMediaTab = (button, shouldFocus = false) => {
    const tablist = button.closest('[data-community-media-tabs]');

    if (!(tablist instanceof HTMLElement)) {
        return;
    }

    const selectedMedia = button.dataset.communityMediaTab;

    tablist.querySelectorAll('[data-community-media-tab]').forEach((tab) => {
        if (!(tab instanceof HTMLButtonElement)) {
            return;
        }

        const isSelected = tab === button;
        tab.setAttribute('aria-selected', String(isSelected));
        tab.tabIndex = isSelected ? 0 : -1;
        tab.classList.toggle('border-orange-500', isSelected);
        tab.classList.toggle('text-orange-700', isSelected);
        tab.classList.toggle('border-transparent', !isSelected);
        tab.classList.toggle('text-slate-500', !isSelected);
        tab.classList.toggle('hover:text-slate-800', !isSelected);
    });

    tablist.querySelectorAll('[data-community-media-panel]').forEach((panel) => {
        if (panel instanceof HTMLElement) {
            panel.hidden = panel.dataset.communityMediaPanel !== selectedMedia;
        }
    });

    if (shouldFocus) {
        button.focus();
    }
};

document.addEventListener('click', (event) => {
    const target = event.target;

    if (!(target instanceof Element)) {
        return;
    }

    const shareButton = target.closest('[data-share-recipe]');

    if (shareButton instanceof HTMLButtonElement) {
        const status = shareButton.parentElement?.querySelector('[data-share-status]');
        const url = shareButton.dataset.shareUrl;
        const title = shareButton.dataset.shareTitle;
        const text = shareButton.dataset.shareText;

        if (!(status instanceof HTMLElement) || !url || !title || !text) {
            return;
        }

        const share = async () => {
            try {
                if (navigator.share) {
                    await navigator.share({ title, text, url });
                    status.textContent = 'Post shared.';

                    return;
                }

                if (!navigator.clipboard?.writeText) {
                    status.textContent = 'Sharing and clipboard are unavailable in this browser.';

                    return;
                }

                await navigator.clipboard.writeText(url);
                status.textContent = 'Post link copied.';
            } catch (error) {
                if (error instanceof DOMException && error.name === 'AbortError') {
                    return;
                }

                status.textContent = 'Could not share or copy the recipe link.';
            }
        };

        void share();

        return;
    }

    const button = target.closest('[data-community-media-tab]');

    if (button instanceof HTMLButtonElement) {
        selectCommunityMediaTab(button);
    }
});

document.addEventListener('keydown', (event) => {
    const target = event.target;

    if (!(target instanceof HTMLButtonElement) || !target.matches('[data-community-media-tab]')) {
        return;
    }

    const tablist = target.closest('[data-community-media-tabs]');
    const tabs = tablist ? Array.from(tablist.querySelectorAll('[data-community-media-tab]')) : [];
    const currentIndex = tabs.indexOf(target);

    if (currentIndex < 0 || tabs.length < 1) {
        return;
    }

    let nextIndex = currentIndex;

    if (event.key === 'ArrowRight') {
        nextIndex = (currentIndex + 1) % tabs.length;
    } else if (event.key === 'ArrowLeft') {
        nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
    } else if (event.key === 'Home') {
        nextIndex = 0;
    } else if (event.key === 'End') {
        nextIndex = tabs.length - 1;
    } else {
        return;
    }

    event.preventDefault();
    const nextTab = tabs[nextIndex];

    if (nextTab instanceof HTMLButtonElement) {
        selectCommunityMediaTab(nextTab, true);
    }
});

document.addEventListener('click', (event) => {
    const target = event.target;

    if (!(target instanceof Element)) {
        return;
    }

    const dropdowns = document.querySelectorAll('[data-navigation-dropdown][open]');
    const selectedLink = target.closest('[data-navigation-dropdown] a');

    dropdowns.forEach((dropdown) => {
        if (selectedLink || !dropdown.contains(target)) {
            dropdown.removeAttribute('open');
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('[data-navigation-dropdown][open]').forEach((dropdown) => {
        dropdown.removeAttribute('open');
    });
});

document.addEventListener('click', (event) => {
    const target = event.target;

    if (!(target instanceof Element)) {
        return;
    }

    const button = target.closest('[data-profile-recipe-view]');
    const grid = document.querySelector('[data-profile-recipes-grid]');

    if (!(button instanceof HTMLButtonElement) || !(grid instanceof HTMLElement)) {
        return;
    }

    const isList = button.dataset.profileRecipeView === 'list';
    grid.classList.toggle('is-list', isList);

    document.querySelectorAll('[data-profile-recipe-view]').forEach((viewButton) => {
        const isSelected = viewButton === button;
        viewButton.setAttribute('aria-pressed', String(isSelected));
        viewButton.classList.toggle('border-orange-200', isSelected);
        viewButton.classList.toggle('bg-orange-50', isSelected);
        viewButton.classList.toggle('text-orange-700', isSelected);
        viewButton.classList.toggle('border-orange-100', !isSelected);
        viewButton.classList.toggle('bg-white', !isSelected);
        viewButton.classList.toggle('text-slate-600', !isSelected);
    });
});

document.addEventListener('change', (event) => {
    const input = event.target;

    if (input instanceof HTMLSelectElement && input.matches('[data-profile-sort-select]')) {
        input.form?.requestSubmit();

        return;
    }

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