function createPostHTML(post) {
    const imageHTML = post.image ? `<div class="grid-1"><img src="${post.image}" alt="post-img" class="imgs" loading="lazy"></div>` : '';
    return `
            <div class="grid-item">
                <div class="feed-ps">
                    <div class="feed">
                    <div class="box-feed">
                            <img src = "${post.user_image}" 
                                class = "image-pf linkprofile" 
                                data-user-id="${post.user_id}"
                                alt="Profile Image" 
                                loading="lazy">
                            <div class="detail-post">
                                <span class = "post-name linkprofile" 
                                data-user-id="${post.user_id}">${post.username}</span>
                                <span class = "post-date">${post.date}</span>
                            </div> 
                        </div>
                        <a href="post-data.php?id=${encodeURIComponent(post.post_id)}&return_url=${encodeURIComponent('home.php')}"
                            <div class="text-post">
                                <div class="grid-2">
                                    <span>#${post.category}</span>
                                    <p class="t-post">${post.text.replace(/\n/g, '<br>')}</p>
                                </div>
                                ${imageHTML}
                            </div>
                        </a>
                        <div class="ar-icon"></div>
                        <div class="radius">
                            <div class="radiu"></div>
                            <div class="radiu"></div>
                            <div class="radiu"></div>
                        </div>
                    </div>
                </div>
            </div>`;
}

function loadMorePosts() {
    fetch('get_posts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(posts => {
        const realtime = document.getElementById('realtime');
        realtime.innerHTML = posts.map(createPostHTML).join('');
        bindProfileClickEvents();
    });
}

function bindProfileClickEvents() {
    document.querySelectorAll('.linkprofile').forEach(function(img) {
        img.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            profileClick(userId);
        });
    });
}

setInterval(loadMorePosts, 20000);

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