<?php
if($logged) { ?>

<div class="container">
    <a href="logout" style="position: absolute; right: 10px; top: 10px;">Log ud</a>

    <h1>Members only</h1>
    <br>
    <form>
    <input type="file" id="fileInput" class="inputfile" multiple accept="image/*" onchange="upload(this.files)">
    <div id="files">
    Loading..
    </div>
    </form>
</div>




<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
//https://www.smashingmagazine.com/2018/01/drag-drop-file-uploader-vanilla-js/
let dropArea = document.querySelector("#files");

let files = [];
axios.get('action.getFiles')
    .then(function(res) {
        dropArea.innerHTML = res.data;
    })



dropArea.addEventListener('drop', function(e) {
  let dt = e.dataTransfer
  let files = dt.files

  upload(files)
}, false)

function upload(files) {
    //konverter til array liste ved at destructure filelisten ind i en ny array
    let list = [
        ...files
    ];
    let formData = new FormData();
    formData.folder = null;
    list.forEach(file => {
        formData.append('file', file)
    });
    axios.post('actions/upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(function(){
        console.log('SUCCESS!!');
    })
    .catch(function(){
        console.log('FAILURE!!');
    });
}


let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
events.forEach(eventName => {
  dropArea.addEventListener(eventName, preventDefaults, false)
})
function preventDefaults (e) {
  e.preventDefault()
  e.stopPropagation()
}

dropArea.addEventListener('dragenter', ready, false)
dropArea.addEventListener('dragover', ready, false)

dropArea.addEventListener('dragleave', unready, false)
dropArea.addEventListener('drop', unready, false)
function ready() {
    dropArea.classList.add('ready');
}
function unready() {
    dropArea.classList.remove('ready');
}
</script>

<?php
}
?>