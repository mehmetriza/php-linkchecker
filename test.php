<?php 
require "cano.php";
extract($_POST);
$cano=new cano([
    "attr"=>[$attr,$attValue],
    "url"=>$url,
    "host"=>$host
]);
$cano->visit();
?>
<link rel="stylesheet" href="table.css">
<div class="table-title">
    <h3>Success Url</h3>
</div>
<table class="table-fill" >
    <thead>
        <tr>
            <th>Name</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody class="table-hover">
    <?php foreach($cano->urls as $link=>$name): ?>
    <tr>
        <td><?=$name?></td>
        <td><?=$link?></td>
    </tr>
    <?php endforeach; ?>
    </<tbody>
</table>

<div class="table-title">
    <h3>404 Url</h3>
</div>
<table class="table-fill">
    <thead>
        <tr>
            <th>Name</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody class="table-hover">
    <?php foreach($cano->notUrls as $link=>$name): ?>
        <tr>
            <td><?=$name?></td>
            <td><?=$link?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="table-title">
    <h3>External Url</h3>
</div>
<table class="table-fill">
    <thead>
        <tr>
            <th>Name</th>
            <th>Link</th>
        </tr>
    </thead>
    <tbody class="table-hover">
    <?php foreach($cano->externalUrls as $link=>$name): ?>
        <tr>
            <td><?=$name?></td>
            <td><?=$link?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>