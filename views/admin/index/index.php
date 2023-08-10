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

<div class="form-group">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="difficulty" class="col-lg-2 control-label"><?=$this->getTrans('difficulty') ?></label>
            <div class="col-lg-4">
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
    <div class="col-lg-6">
        <div class="form-group">
            <label for="locale" class="col-lg-2 control-label"><?=$this->getTrans('locale') ?></label>
            <div class="col-lg-4">
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
<div class="form-group">
<?=$pagination->getHtml($this, ['action' => 'index']) ?>
    <form class="form-horizontal col-lg-12" method="POST" id="groupForm">
    <?=$this->getTokenField() ?>
        <div class="table-responsive">
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
        </div>
    </form>
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
