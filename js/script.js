let popupContainer = document.querySelector('.boxpost');
let fades = document.querySelector('.fade');


function openPopup() {
    fades.classList.toggle("active");
    popupContainer.classList.toggle("show-post-pop");
    document.body.style.overflow = 'hidden';
}
function closePopups() {
    fades.classList.remove("active");
    popupContainer.classList.remove("show-post-pop");
    document.body.style.overflow = '';
    document.getElementById('postForm').reset();
    document.querySelector('.warn').textContent = "";

    imgPv.src = "";
    imgPv.style.display = 'none';
    imgInput.value = "";
    selectedCategory = null;
}



const imgPv = document.getElementById("previewImage");
const imgInput = document.getElementById("imageUpload");
function previewImg(imageUpload) {
    if (imageUpload.files) {
        const imgfile = new FileReader();
        imgfile.readAsDataURL(imageUpload.files[0]);
        imgfile.onload = (e) => {
            imgPv.src = e.target.result;
            imgPv.style.display = 'block';
        }
    }
    return false;
}
removeImg = () => {
    imgPv.src = "";
    imgPv.style.display = 'none';
    imgInput.value = "";
}


let sct = document.querySelector(".selector");
let lists = document.querySelector(".lists");

sct.addEventListener("click", () => {
    lists.classList.toggle("showpost");
});

let list = document.querySelectorAll(".list");

list.forEach((e) => {
    let inp = document.querySelector(".selector input");
    e.addEventListener("click", () => {
        inp.value = e.innerHTML;
        lists.classList.remove("showpost");
    })
});



let logdrop = document.querySelector(".logout-drop");
function submitlogout() {
    logdrop.classList.toggle("show-log");
}

function logcancel() {
    logdrop.classList.remove("show-log");
}




document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('postForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            submitpost(e);
        });
    }
});

let selectedCategory = null;

document.querySelectorAll('.list').forEach(item => {
    item.addEventListener('click', function () {

        selectedCategory = this.getAttribute('data-value');

        document.getElementById('headpost').value = this.innerText;
    });
});

function submitpost(e) {
    const form = e.target;
    const text = form.text.value;
    if (!selectedCategory && text.trim() === "") {
        document.querySelector('.warn').textContent = "กรุณากรอกข้อมูล!";
        return;
    } else if (!selectedCategory) {
        document.querySelector('.warn').textContent = "กรุณาระบุกระทู้!";
        return;
    } else if (text.trim() === "") {
        document.querySelector('.warn').textContent = "กรุณาใส่รายละเอียด!";
        return;
    } else {

        const formData = new FormData(form);
        formData.append('category', selectedCategory);
        const currentPage = window.location.pathname.includes('profile') ? 'profile' : 'home';
        formData.append('page', currentPage);
        
        $.ajax({
            url: 'post.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === "success") {
                    closePopups();
                    if (currentPage === 'home') {
                        loadMorePosts();
                    }
                }
            }
        });
    }

}
