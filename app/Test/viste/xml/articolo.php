<articolo>
    <titolo><?php echo $output['titolo']?></titolo>
    <contenuti><?php echo $output['body']?></contenuti>
    <tag>
        <?php
            foreach ($output['tag'] as $value)
                echo '<item>'.$value.'</item>';
        ?>
    </tag>
    <categoria>
        <?php
            foreach ($output['categoria'] as $value)
                echo '<item>'.$value.'</item>';
        ?>
    </categoria>
    <galleria>
        <?php
            foreach ($output['gallery'] as $value)
                echo '<item src="'.$value['src'].'" title="'.$value['titolo'].'" alt="'.$value['alt'].'" />';
        ?>
    </galleria>
</articolo>