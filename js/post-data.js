document.addEventListener('DOMContentLoaded', () => {
    let menuButton = document.getElementById('menu-bt');
    let dropMenu = document.querySelector('.dropmenu');

    if (menuButton) {
        menuButton.addEventListener('click', () => {
            dropMenu.classList.toggle("show-menu");
        });
    }

    document.addEventListener('click', (event) => {
        if (menuButton && !menuButton.contains(event.target) && !dropMenu.contains(event.target)) {
            dropMenu.classList.remove("show-menu");
        }
    });

    let sct = document.querySelector(".selectoredit");
    let lists = document.querySelector(".listsedit");

    if (sct) {
        sct.addEventListener("click", () => {
            lists.classList.toggle("showpostedit");
        });
    }

    let list = document.querySelectorAll(".liste");

    list.forEach((e) => {
        let inp = document.querySelector(".selectoredit input");
        e.addEventListener("click", () => {
            inp.value = e.innerHTML;
            lists.classList.remove("showpostedit");
        })
    });

    
});



function submitCom(e) {
    e.preventDefault();
    const form = document.getElementById('postcomment');
    const text_com = form.commentsend.value;

    if (text_com.trim() === "") {
        return;
    } else {
        const formData = new FormData(form);

        $.ajax({
            url: 'post-comment.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    form.reset();
                    fetchComs();
                }
            }
        });
    }
}

function createComHTML(post) {

    return `<div class="comreal">
                <div class="comment-feed">
                    <div class="curved-triangle"></div>
                    <div class="comment-content">
                        <div class="user-comment">
                            <img src="${post.image}" class="user-com linkprofile" data-user-id="${post.iduser}" >
                            <div class="detail-com">    
                                <div class="icon-u">
                                    <span class="com-name linkprofile" data-user-id="${post.iduser}">${post.names}</span>
                                    <span class="material-symbols-outlined user-icon ${post.check ? 'show-icon' : 'hidden-icon'}">person_edit</span>
                                </div>
                                <span class="com-date">${post.date}</span>
                            </div>
                        </div>
                        <span class="text-com">${post.text.replace(/\n/g, '<br>')}</span>
                        ${post.delete || post.admin ?
                        `<div class = "delete-com">
                            <button type = "button" onclick="deletecomment(this)"><span class="material-symbols-outlined de-comment">chat_error</span></button>
                        </div>`
                        : ``}
                        <form method="POST" id ="delete-comment-p">
                        <div class = "box-decom">
                            <input type="hidden" name="comde_id" value="${post.idcom}">
                            <ul> 
                                <li class = "listcom" onclick="submitComment(event)">DELETE</li>
                                <li class = "listcom" onclick="cancelComment(this)">CANCLE</li>
                            </ul>
                        </div>
                        </form> 
                    </div>
                </div>
            </div>`;
}

function fetchComs() {

    const data = {
        user_id: post_user_id,
        postid: postid
    };

    fetch('get_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(posts => {
        const realcom = document.getElementById('realcom');
        realcom.innerHTML = posts.map(createComHTML).join('');
        document.getElementById('comss').textContent = posts.length + " Comment";
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

setInterval(fetchComs, 5000);


let warnAlert = document.querySelector('.warns');

function deletepost() {
    warnAlert.classList.toggle("show-delete");
}

function deletecancel() {
    warnAlert.classList.remove("show-delete");
}


function deletecomment(button) {
    const dropcom = button.closest('.comment-content').querySelector('.box-decom');
    dropcom.classList.toggle("submit-decom");
}

function cancelComment(item) {
    const dropcom = item.closest('.box-decom');
    dropcom.classList.remove("submit-decom");

}

function submitComment(e){
    e.preventDefault();
    const form = document.getElementById('delete-comment-p');

    const formData = new FormData(form);

    $.ajax({
        url: 'comment_de.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                fetchComs();
            }
        }
    });
}
