<?php
/*------------------------------------------------------------

Author      : Richard Firdaus Oeyliawan (richfir@gmail.com)

File PHP ini dibuat untuk menampilkan buku rekomendasi yang
memiliki kemiripan dari buku yang dipilih secara spesifik
Skor kemiripan diperoleh dari hasil perhitungan menggunakan
algoritma TF-IDF dan Vector Space Model, kemudian dihitung
menggunakan Cosine Similarity.

Diharapkan dengan adanya fitur rekomendasi buku ini, pengguna
slims.umn.ac.id akan semakin dimudahkan dalam mencari buku
yang memiliki kemiripan konten dengan buku yang dicari 
apabila buku yang dicari ternyata sedang dipinjam atau untuk
memperluas referensi pencarian.

-------------------------------------------------------------*/

?>

<?php 

// author perlu ditambahkan ke dalam view buku_rekomendasi
   if(isset($_GET['id'])){
    $biblio_id = $_GET['id'];
    $select_query ="SELECT biblio_2 as biblio_id, image_2 as image, title_2 as title , author_2 as author_name , similarity_score FROM `buku_rekomendasi` WHERE biblio_1 LIKE $biblio_id AND similarity_score !=1 UNION 
                    SELECT biblio_1 as biblio_id, image_1 as image, title_1 as title , author_1 as author_name, similarity_score FROM `buku_rekomendasi` WHERE biblio_2 LIKE $biblio_id AND similarity_score !=1
                    GROUP BY biblio_id ORDER BY similarity_score DESC
                    LIMIT 10";
    $execute = $dbs->query($select_query);

    }
?>


<?php if(isset($_GET['p'])){ ?>
<?php if($_GET['p'] == 'show_detail'){ ?>
<?php if($execute->num_rows > 0) {?>
<div class='row'>
    <div class='span8'>
    <div class='tagline'>
        <?php echo __('Recommendation books based on this book'); ?>
    </div>
    <div class='recommendation-books clearfix'>
        <div id="next-button"></div>
        
        <ul class="bxslider list-centered">
       
        <?php while($data = $execute->fetch_assoc()){ ?>
        <li class="rec-slider-li">
            <div class="recommendation-item">
            <?php if (!empty($data['image'])) :
            ?>
            <a href="./index.php?p=show_detail&id=<?php echo $data['biblio_id'] ?>" title="<?php echo $data['title'] ?>"><img src="images/docs/<?php echo $data['image'] ?>" /></a>
            <?php
            else:
            ?>
            <a href="./index.php?p=show_detail&id=<?php echo $data['biblio_id'] ?>" title="<?php echo $data['title'] ?>"><img src="./template/default/img/nobook.png" /></a>
            <?php
            endif;
            ?>
            <!--<img src='template\default\img\nobook.png'>-->
            <div class='recommendation-books-title'>
                <?php if(strlen($data['title']) > 30 ){ ?>
                <a href="./index.php?p=show_detail&id=<?php echo $data['biblio_id'] ?>" title="<?php echo $data['title'] ?>">
                <?php echo substr($data['title'],0,30)."...";?>
                </a>
                <?php }else{ ?>
                <a href="./index.php?p=show_detail&id=<?php echo $data['biblio_id'] ?>" title="<?php echo $data['title'] ?>">
                <?php echo $data['title'];?>
                </a>
                <?php } ?>
                
            </div>
            <div class='recommendation-books-author'>
                <?php if(strlen($data['author_name']) > 10 ){ ?>
                <a href="?author=<?php echo $data['author_name']?>&search=Search" title="Click to view others documents with this author">
                <?php echo substr($data['author_name'],0,10)."...";?>
                </a>
                <?php }else{ ?>
                <a href="?author=<?php echo $data['author_name']?>&search=Search" title="Click to view others documents with this author">
                <?php echo $data['author_name'];?>
                </a>
                <?php } ?>
            </div>
            <div class='recommendation-books-score'>
                <?php echo $data['similarity_score'];?>
            </div>
            <br/>
            </div>
        </li>
        <?php } ?>
        </ul>
    </div>
    </div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>