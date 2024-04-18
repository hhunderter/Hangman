<?php

/** @var \Ilch\View $this */

/** @var Modules\Hangman\Libs\Hangman $hangmanLib */
$hangmanLib = $this->get('hangmanLib');

/** @var array $localeList */
$localeList = $this->get('localeList');

/** @var \Ilch\Pagination $pagination */
$pagination = $this->get('pagination');
?>
<h1><?=$this->getTrans('manage') ?></h1>

<div class="row mb-3">
    <div class="col-xl-6">
        <div class="row mb-3">
            <label for="difficulty" class="col-xl-2 col-form-label"><?=$this->getTrans('difficulty') ?></label>
            <div class="col-xl-4">
                <select class="chosen-select form-control" id="difficulty" name="difficulty" data-placeholder="">
                    <option value="" <?=(empty($this->getRequest()->getParam('difficulty'))) ? 'selected=""' : '' ?>><?=$this->getTrans('all') ?></option>
                    <?php
                    foreach ($hangmanLib->getDifficultyTypes() as $id => $name) {
                        ?>
                        <option value="<?=$id ?>" <?=($this->getRequest()->getParam('difficulty') == $id) ? 'selected=""' : '' ?>><?=$this->getTrans($name) ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="row mb-3">
            <label for="locale" class="col-xl-2 col-form-label"><?=$this->getTrans('locale') ?></label>
            <div class="col-xl-4">
                <select class="chosen-select form-control" id="locale" name="locale" data-placeholder="">
                    <option value="" <?=(empty($this->getRequest()->getParam('locale'))) ? 'selected=""' : '' ?>><?=$this->getTrans('all') ?></option>
                    <?php
                    foreach ($localeList as $id => $name) {
                        ?>
                        <option value="<?=$id ?>" <?=($this->getRequest()->getParam('locale') == $id) ? 'selected=""' : '' ?>><?=$this->getTrans($name) ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
<?=$pagination->getHtml($this, ['action' => 'index']) ?>
    <div class="col-xl-12">
        <form method="POST" id="groupForm">
        <?=$this->getTokenField() ?>
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width">
                    <col class="icon_width">
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('text') ?></th>
                    <th><?=$this->getTrans('difficulty') ?></th>
                    <th><?=$this->getTrans('locale') ?></th>
                </tr>
                </thead>

                <tbody>
                <?php
                foreach ($this->get('entries') ?? [] as $entry) {
                    ?>
                    <tr>
                        <td><?=$this->getDeleteIcon(['action' => 'del', 'id' => $entry->getId()]) ?></td>
                        <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $entry->getId()]) ?></td>
                        <td><?=$this->escape($entry->getText()) ?></td>
                        <td><?=$this->getTrans($hangmanLib->getDifficultyType($entry->getDifficulty())) ?></td>
                        <td><?=$this->getTrans($entry->getLocale()) ?></td>
                    </tr>
                    <?php
                } ?>

                </tbody>
            </table>
        </form>
    </div>
</div>
<?=$pagination->getHtml($this, ['action' => 'index']) ?>
<script>
    $('#difficulty').chosen();
    $('#locale').chosen();

    $(function() {
        $('#difficulty').change(function() {
            let urladd = "";
            if ($(this).val() !== "") {
                urladd = urladd+"/difficulty/"+$(this).val();
            }
            if ($('#locale').val() !== "") {
                urladd = urladd+"/locale/"+$('#locale').val();
            }
            window.open("<?=$this->getUrl(['action' => 'index']) ?>"+urladd, "_self");
        })
    })
    $(function() {
        $('#locale').change(function() {
            let urladd = "";
            if ($(this).val() !== "") {
                urladd = urladd+"/locale/"+$(this).val();
            }
            if ($('#difficulty').val() !== "") {
                urladd = urladd+"/difficulty/"+$('#difficulty').val();
            }
            window.open("<?=$this->getUrl(['action' => 'index']) ?>"+urladd, "_self");
        })
    })
</script>
