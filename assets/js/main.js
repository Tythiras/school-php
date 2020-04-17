let dropArea = document.querySelector("body");
let container = document.querySelector(".container");
let title = document.getElementsByTagName("title")[0].innerHTML;
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
            document.querySelector("#files").innerHTML = res.data;
        })  
}



dropArea.addEventListener('drop', function(e) {
    let dt = e.dataTransfer
    let files = dt.files
    if(files.length > 0) {
        upload(files)
    }
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
        alert('Request Error');
    });
}

//move file stuff
let dragginInternalFolder = false;
let lasthover;
let initial;
function drag(e) {
    e.dataTransfer.setData("id", e.target.dataset.id)
    e.dataTransfer.setData("type", e.target.dataset.type)
    container.classList.add('moving');
    dragginInternalFolder = true;
    inital = e.target.dataset.id;
}
function dragOver(e) {
    if(dragginInternalFolder) {
        if(e.target.dataset.id!=inital) {
            e.target.classList.add('draggedOver')
            lasthover = e.target.dataset.id;
            e.preventDefault();

        }
    }
}
function dragLeave(e) {
    if(dragginInternalFolder) {
        if(lasthover&&e.target.dataset.id) {
            e.target.classList.remove('draggedOver');

        }
    }
}
function drop(e, el) {
    if(dragginInternalFolder) {
        dragginInternalFolder = false;
        let result = confirm("Er du sikker på du vil flytte filen / mappen hertil?");
        el.classList.remove('draggedOver');
        if(result) {
            let data = new FormData();
            data.append('targetID', e.dataTransfer.getData('id'));
            data.append('targetType', e.dataTransfer.getData('type'));
            data.append('targetFolder', el.dataset.id);
            axios.post('action.move', data)
                .then(function({data}){
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
    }
}

function del(e, el) {
    if(dragginInternalFolder) {
        dragginInternalFolder = false;
        let result = confirm("Er du sikker på du vil slette denne fil / mappe?");
        if(result) {
            let data = new FormData();
            data.append('targetID', e.dataTransfer.getData('id'));
            data.append('targetType', e.dataTransfer.getData('type'));
            axios.post('action.delete', data)
                .then(function({data}){
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
    }
}
//upload file stuff
let events = ['dragenter', 'dragover', 'dragleave', 'drop'];
events.forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false)
})
function preventDefaults (e) {
    e.preventDefault()
    e.stopPropagation()
}


dropArea.addEventListener('dragenter', ready, false)
dropArea.addEventListener('dragleave', unready, false)
dropArea.addEventListener('drop', function(e) {
    if(containsFiles(e)) {
        closeModal("droparea")
    }
    if(e.dataTransfer.getData('type')) {
        dragginInternalFolder = false;
        container.classList.remove('moving');

    }

}, false)
let lastenter;
function ready(e) {
    if(containsFiles(e)) {
        lastenter = event.target;
        openModal("droparea")
    }
}
function unready(e) {
    if(containsFiles(e)) {
        if(lastenter == event.target) {
            closeModal("droparea")
        }
    }
}


//https://css-tricks.com/snippets/javascript/test-if-dragenterdragover-event-contains-files/
function containsFiles(event) {
    if (event.dataTransfer.types) {
        for (var i = 0; i < event.dataTransfer.types.length; i++) {
            if (event.dataTransfer.types[i] == "Files") {
                return true;
            }
        }
    }
    return false;
}