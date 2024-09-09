<?php

/** @var \Ilch\View $this */

/** @var Modules\User\Mappers\User $userMapper */
$userMapper = $this->get('userMapper');

/** @var Modules\Hangman\Models\Highscore $entries */
$entries = $this->get('entries');
?>
<h1>
    <?=$this->getTrans('hangman') ?>
</h1>
<div class="row mb-3">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="<?=$this->getUrl(['action' => 'index']) ?>"><?=$this->getTrans('game') ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?=$this->getUrl(['action' => 'highscore']) ?>"><?=$this->getTrans('highscore') ?></a>
        </li>
    </ul>
</div>
<div class="teams" id="hangman-container">
    <div class="col-xl-12" id="hangman-form-container">
        <?php
        if ($entries) {
            ?>
            <table class="table table-hover table-striped">
                <colgroup>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th><?=$this->getTrans('user') ?></th>
                    <th><?=$this->getTrans('score') ?></th>
                    <th><?=$this->getTrans('games') ?></th>
                </tr>
                </thead>
                <?php
                foreach ($entries ?? [] as $entry) {
                    $user = $this->get('userMapper')->getUserById($entry->getUserId()); ?>
                    <tbody>
                    <tr>
                        <td><?=$this->escape($user->getName()) ?></td>
                        <td><?=$entry->getScore() ?></td>
                        <td><?=$entry->getGames() ?></td>
                    </tr>
                    </tbody>
                    <?php
                } ?>
            </table>
            <?php
        } else {
            ?>
            <div class="alert alert-danger">
                <?=$this->getTrans('noentries') ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
