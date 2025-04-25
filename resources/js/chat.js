// Chat page in user profile (chat.blade.php)
// The function to display "DELETE" small modal when LONG-PRESS a chat message
document.querySelectorAll('.long-press-target').forEach(target => {
    let timer;
    const duration = 600;

    target.addEventListener('mousedown', (e) => {
        timer = setTimeout(() => {
            const id = target.dataset.chatId;
            document.getElementById('dropdown-' + id).style.display = 'block';
        }, duration);
    });

    target.addEventListener('mouseup', () => clearTimeout(timer));
    target.addEventListener('mouseleave', () => clearTimeout(timer));

    // Optional: click anywhere to close
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.long-press-target')) {
            // limit the target for this function only for ".chat-menu"
            // if you use "dropdown-menu" instead, navigation bar will not show dropdown menu.
            document.querySelectorAll('.chat-menu').forEach(menu => menu.style.display = 'none');
        }
    });
});

document.getElementById('image').addEventListener('change', function () {
    const fileName = this.files[0]?.name || '';
    document.getElementById('file-name').textContent = fileName;
});
