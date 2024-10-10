document.addEventListener('DOMContentLoaded', () => {
    let selectedCategoryElement = document.getElementById('selectedCategory');
    let selectedCategory = selectedCategoryElement ? selectedCategoryElement.getAttribute('data-value') : null;

    document.querySelectorAll('.liste').forEach(item => {
        item.addEventListener('click', function() {
            selectedCategory = this.getAttribute('data-value');
            selectedCategoryElement.value = this.innerText;
        });
    });

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const text = form.textedit.value;

        if (!selectedCategory && text.trim() === "") {
            document.querySelector('.warnedit').textContent = "กรุณากรอกข้อมูล!";
            return;
        } else if (!selectedCategory) {
            document.querySelector('.warnedit').textContent = "กรุณาระบุกระทู้!";
            return;
        } else if (text.trim() === "") {
            document.querySelector('.warnedit').textContent = "กรุณาใส่รายละเอียด!";
            return;
        } else {
            document.querySelector('.warnedit').textContent = "";

            const formData = new FormData(form);
            formData.append('category', selectedCategory);

            try {
                fetch('post-edit.php', {
                    method: 'POST',
                    body: formData,
                });
                closePopup();
                setTimeout(() => {
                    location.reload();
                }, 500);
            } catch (error) {
                console.error('Error during fetch:', error);
            }
        }
    });
});



let popupedit = document.querySelector('.boxpostedit');
let faded = document.querySelector('.fadeedit');

function editpost() {
    popupedit.classList.toggle("edit-pop");
    faded.classList.toggle("tappost-show");
    document.body.style.overflow = 'hidden';
}

function closePopup() {
    popupedit.classList.remove("edit-pop");
    faded.classList.remove("tappost-show");
    document.body.style.overflow = '';  
    document.getElementById('editForm').reset();
    document.querySelector('.warnedit').textContent = "";

    if (imgUp && imgUp.value) {
        imgEd.src = imgUp.value;
        imgEd.style.display = 'block';
        imgIn.value = '';
    } else {
        imgEd.src = "";
        imgEd.style.display = 'none';
        imgIn.value = '';
    }
}

const imgEd = document.getElementById("preview-edit");
const imgIn = document.getElementById("imageedit");
const imgUp = document.getElementById("backupedit");


function preview_edit(imageedit){
    if (imageedit.files && imageedit.files[0]) {
        const imgf = new FileReader();
        imgf.readAsDataURL(imageedit.files[0]);
        imgf.onload=(e) => {
            imgEd.src = e.target.result;
            imgEd.style.display = 'block';
        }
    }
}
function remove_edit() {
    const imgEd = document.getElementById("preview-edit");
    const imgIn = document.getElementById("imageedit");
    const imgDeleted = document.getElementById("image_deleted");

    imgEd.src = "";
    imgEd.style.display = 'none';
    imgIn.value = "";

    if (imgDeleted) {
        imgDeleted.value = '1';
    }
}