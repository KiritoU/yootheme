<?php

$el = $this->el('div', [

    'uk-accordion' => [
        'multiple: {multiple};',
        'collapsible: {0};' => $props['collapsible'] ? 'true' : 'false',
    ],

]);

?>

<?= $el($props, $attrs) ?>

    <?php foreach ($children as $child) :

        $content = $this->el('div', [

            'class' => [
                'uk-accordion-content',
            ],

        ]);

    ?>
    <div class="el-item">

        <a class="el-title uk-accordion-title" href="#"><?= $child->props['title'] ?></a>

        <?= $content($props) ?>
            <?= $builder->render($child, ['element' => $props]) ?>
        <?= $content->end() ?>

    </div>
    <?php endforeach ?>

</div>
