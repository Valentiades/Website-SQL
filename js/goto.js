function profileClick(userId) {
    fetch('redirect.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ user_id: userId }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;  
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.linkprofile').forEach(function(img) {
        img.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            profileClick(userId);
        });
    });
});
