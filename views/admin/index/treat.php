<?php

/** @var \Ilch\View $this */

/** @var Modules\Hangman\Libs\Hangman $hangmanLib */
$hangmanLib = $this->get('hangmanLib');

/** @var Modules\Hangman\Models\Words $entries */
$entries = $this->get('entrie');
?>
<h1><?=($entries->getId()) ? $this->getTrans('edit') : $this->getTrans('add') ?></h1>
<form role="form" class="form-horizontal" method="POST">
    <?=$this->getTokenField() ?>

    <div class="form-group <?=$this->validation()->hasError('text') ? 'has-error' : '' ?>">
        <label for="text" class="col-lg-2 control-label">
            <?=$this->getTrans('text') ?>
        </label>
        <div class="col-lg-4">
            <input class="form-control"
                   type="text"
                   id="text"
                   name="text"
                   value="<?=$this->escape($this->originalInput('text', $entries->getText())) ?>" />
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('difficulty') ? ' has-error' : '' ?>">
        <label for="difficulty" class="col-lg-2 control-label">
            <?=$this->getTrans('difficulty') ?>
        </label>
        <div class="col-lg-8">
            <select class="form-control" id="difficulty" name="difficulty">
            <?php foreach ($hangmanLib->getDifficultyTypes() as $id => $name) : ?>
                <option value="<?=$id ?>" <?=($this->originalInput('difficulty', $entries->getDifficulty())) == $id ? 'selected=""' : '' ?>><?=$this->getTrans($name) ?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group<?=$this->validation()->hasError('locale') ? ' has-error' : '' ?>">
        <label for="locale" class="col-lg-2 control-label">
            <?=$this->getTrans('locale') ?>
        </label>
        <div class="col-lg-8">
            <select class="form-control" id="locale" name="locale">
                <option value="" <?=($this->originalInput('locale', $entries->getLocale())) == '' ? 'selected=""' : '' ?>><?=$this->getTrans('all') ?></option>
                <?php foreach ($this->get('localeList') as $locale => $name) : ?>
                    <option value="<?=$locale ?>" <?=($this->originalInput('locale', $entries->getLocale() ?? $this->get('locale'))) == $locale ? 'selected=""' : '' ?>><?=$name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?=($entries->getId()) ? $this->getSaveBar('updateButton') : $this->getSaveBar('addButton') ?>
</form>
