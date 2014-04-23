<h2><?php echo $output['titolo']?></h2>
<div><?php echo $output['body']?></div>
<div>
    <p>
        <a href="<?php echo \Concerto\Utility::costruisci_url_assoluto_app('/1/2/3','English US');?>">English US Link</a><br />
        <a href="<?php echo \Concerto\Utility::costruisci_url_assoluto('/articolo-del-cms.html','Italiano');?>">Articolo in Italiano</a><br />
        <a href="<?php echo \Concerto\Utility::costruisci_url_assoluto_app('/Controller/View/1.xml','Italiano');?>">XML Page</a><br />
        <a href="<?php echo \Concerto\Utility::costruisci_url_assoluto_app('/Controller/View/1.json','Italiano');?>">JSON Page</a>
    </p>
</div>
<div>Tag: 
    <ul>
        <?php
            foreach ($output['tag'] as $value)
                echo '<li>'.$value.'</li>';
        ?>
    </ul>
</div>
<div>Categoria: 
    <ul>
        <?php
            foreach ($output['categoria'] as $value)
                echo '<li>'.$value.'</li>';
        ?>
    </ul>
</div>
<div>gallery: 
    <div>
        <?php
            foreach ($output['gallery'] as $value)
                echo '<img src="'.$value['src'].'" title="'.$value['titolo'].'" alt="'.$value['alt'].'" style="margin-right:10px;"/>';
        ?>
    </div>
</div>