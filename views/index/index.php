<?php

/** @var \Ilch\View $this */

/** @var Modules\Hangman\Libs\Hangman $hangmanLib */
$hangmanLib = $this->get('hangmanLib');
?>
<h1>
    <?=$this->getTrans('hangman') . $this->get('gettext') ?>
</h1>
<div class="form-group">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="<?=$this->getUrl(['action' => 'index']) ?>"><?=$this->getTrans('game') ?></a>
        </li>
        <li class="">
            <a href="<?=$this->getUrl(['action' => 'highscore']) ?>"><?=$this->getTrans('highscore') ?></a>
        </li>
    </ul>
</div>
<div class="teams" id="hangman-container">
    <div class="col-lg-12" id="hangman-form-container">
        <form method="POST">
            <?=$this->getTokenField() ?>
            <?=$hangmanLib->displayGame($this, false) ?>
        </form>
    </div>
</div>
