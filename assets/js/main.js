let dropArea = document.querySelector("#files");
let title = document.getElementsByTagName("title")[0].innerHTML;;
let folder = null;
let files = [];
let currParams;
currParams = new URL(window.location.href).searchParams;
let p = currParams.get('f');
if(p) folder = p;
history.replaceState({uid: folder}, title, window.location.href);

window.addEventListener('popstate', function(event) {
    if(event.state) {
        folder = event.state.uid;
        loadFiles();
    }
});

function downloadFile(uid) {
    document.getElementById('iframe').src = window.location.href.split("?")[0] + "action.download?id="+uid;
}
function goToFolder(uid, e = false) {
    if(e&&e.ctrlKey) {
        let newParams = currParams;
        newParams.set('f', uid);
        window.open(window.location.href.split("?")[0]+"?"+newParams.toString(), '_blank');
    } else {
        currParams.set('f', uid);
        folder = uid;
        console.log('pushing new state');
        history.pushState({uid: uid}, title, "?"+currParams.toString());
        loadFiles();
    }
}

function createFolder() {
    let inputEl = document.querySelector("#folderName");
    if(inputEl.value.trim() != "") {
        let data = new FormData();
        data.append('folder', folder);
        data.append('name', inputEl.value);
        axios.post('action.newFolder', data)
            .then(function({data}){
                const res = data;
                if(res.success) {
                    closeModal();
                    loadFiles();
                    inputEl.value = '';
                } else {
                    console.error(data);
                    alert('Error: '+res.message);
                }
            })
            .catch(function(err){
                console.log(err);
                alert('Request Error');
            });
    }
}


//https://www.smashingmagazine.com/2018/01/drag-drop-file-uploader-vanilla-js/

loadFiles();
function loadFiles() {
    axios.get(folder ? 'action.getFiles?id=' + folder : 'action.getFiles')
        .then(function(res) {
            dropArea.innerHTML = res.data;
        })  
}



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
    formData.append("folder", folder);
    list.forEach(file => {
        formData.append('file[]', file)
    });
    axios.post('action.upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(function({data}){
        const res = data;
        if(res.success) {
            loadFiles();
        } else {
            console.error(data);
            alert('Error: '+res.message);
        }
    })
    .catch(function(err){
        console.log(err);
        alert('Request Error');
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