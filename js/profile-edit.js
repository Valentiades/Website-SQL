let fade = document.querySelector('.fade-edit');
let edit_pop = document.querySelector('.edit-form');

function openEdit(){
    fade.classList.toggle("show-edit-fade");
    edit_pop.classList.toggle("show-edit-pop");
}

function closeEdit(){
    fade.classList.remove("show-edit-fade");
    edit_pop.classList.remove("show-edit-pop");
    document.getElementById('edit').reset();
    document.querySelector('.warn-pf').textContent = "";

        imgEd.src = imgUp.src;
        imgEd.style.display = 'block';
        imgIn.value = '';
}

function submitprofile(e){
    e.preventDefault();
    const form = document.getElementById('edit');
    const firstname = form.querySelector('.input_fname').value;
    const fileInput = document.getElementById('file-upload');
    const file = fileInput.files[0];
    const maxSize = 10 * 1024 * 1024;

    if (firstname.length > 30) {
        document.querySelector('.warn-pf').textContent = "ชื่อของคุณเกิน 40 ตัวอักษร";
        return;
    } if (file) {
        if (file.size > maxSize) {
            document.querySelector('.warn-pf').textContent = "รูปภาพขนาดเกิน 10MB";
            return;
        }
    } if (firstname.trim() === ""  && !file) {
        return;
    } else {

        const formData = new FormData(form);
        
        $.ajax({
            url: 'profile-edit.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                setTimeout(() => {
                    location.reload();
                }, 300);
                closeEdit();
            }
        })
    }
}

const imgEd = document.getElementById("picture-data");
const imgIn = document.getElementById("file-upload");
const imgUp = document.getElementById("userimage");


function preview_pf(imageedit){
    if (imageedit.files && imageedit.files[0]) {
        const imgf = new FileReader();
        imgf.readAsDataURL(imageedit.files[0]);
        imgf.onload=(e) => {
            imgEd.src = e.target.result;
            imgEd.style.display = 'block';
        }
    }
}