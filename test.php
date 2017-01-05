<?php 
ini_set('memory_limit', '-1');
require "cano.php";
extract($_POST);
$cano=new cano([
    "attr"=>[$attr,$attrValue],
    "url"=>$url,
    "host"=>$host
]);
$cano->visit();
$nestedUrls=$cano->findChildren($cano->urlsMap,0);
?>
<link rel="stylesheet" href="table.css">
<a href="index.html" id="back" >BACK</a>
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

<div class="table-title">
    <h3>Url Scheme</h3>
</div>
<div class="tree" >
<?php 
renderList($nestedUrls);
function renderList(array $data) {
   echo '<ul>';
   foreach ($data as $item) {
      echo '<li>';
        echo $item["url"];
        renderList($item["children"]);
      echo '</li>';
   }
   echo '</ul>';
}
?>  
</div>