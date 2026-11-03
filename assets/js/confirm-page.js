(() => {
    const token = new URLSearchParams(window.location.search).get('token') || '';
    const tokenInput = document.getElementById('confirmation-token');
    const submitButton = document.getElementById('confirm-submit');
    const status = document.getElementById('confirm-status');

    if (!(tokenInput instanceof HTMLInputElement) ||
        !(submitButton instanceof HTMLButtonElement) ||
        !(status instanceof HTMLElement)) {
        return;
    }

    if (!/^[a-f0-9]{64}$/.test(token)) {
        status.textContent = 'Bekräftelselänken är ogiltig eller saknar kod.';
        return;
    }

    tokenInput.value = token;
    submitButton.disabled = false;
})();
