<?php
if($logged) { ?>

<div class="container">
    <a href="logout" style="position: absolute; right: 10px; top: 10px;">Log ud</a>

    <h1 class="title is-1">Members only</h1>
    <br>
    <form>
    <input type="file" id="fileInput" class="inputfile" multiple accept="image/*" onchange="upload(this.files)">
    <button class="button is-dark modal-btn" style="float: right;" data-modal="newFolder" type="button">Opret mappe</button>
    <div id="files">
    Loading..
    </div>
    </form>
</div>

<div class="modal" data-modal="newFolder">
    <div class="modal-background"></div>
    <div class="modal-content">
        <h2 class="title is-3">Opret mappe</h2>
        <input type="text" name="" id="folderName" class="input" placeholder="Ex. Dansk, Noter">
        <button class="button is-info space" onclick="createFolder()" type="button">Opret</button>
    </div>
    <button class="modal-close is-large" aria-label="close"></button>
</div>



<iframe id="iframe" style="display:none;"></iframe>

<script src="assets/js/modal.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="assets/js/main.js"></script>

<?php
}
?>