<h1><?=$this->getTrans('settings') ?></h1>
<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>

    <div class="form-group <?=$this->validation()->hasError('Guest_Allow') ? 'has-error' : '' ?>">
        <label for="Guest_Allow" class="col-lg-2 control-label">
            <?=$this->getTrans('Guest_Allow') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="Guest_Allow-yes" name="Guest_Allow" value="1" <?=($this->originalInput('Guest_Allow', $this->get('Guest_Allow')))?'checked="checked"':'' ?> />
                <label for="Guest_Allow-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="Guest_Allow-no" name="Guest_Allow" value="0"  <?=(!$this->originalInput('Guest_Allow', $this->get('Guest_Allow')))?'checked="checked"':'' ?> />
                <label for="Guest_Allow-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('Days_Old_Del') ? 'has-error' : '' ?>">
        <label for="Days_Old_Del" class="col-lg-2 control-label">
            <?=$this->getTrans('Days_Old_Del') ?>:
        </label>
        <div class="col-lg-4">
            <input type="number"
                   class="form-control"
                   id="Days_Old_Del"
                   name="Days_Old_Del"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Days_Old_Del', $this->get('Days_Old_Del'))) ?>"
                   required />
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('Letter_Btn') ? 'has-error' : '' ?>">
        <label for="Letter_Btn" class="col-lg-2 control-label">
            <?=$this->getTrans('Letter_Btn') ?>:
        </label>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="Letter_Btn-yes" name="Letter_Btn" value="1" <?=($this->originalInput('Letter_Btn', $this->get('Letter_Btn')))?'checked="checked"':'' ?> />
                <label for="Letter_Btn-yes" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="Letter_Btn-no" name="Letter_Btn" value="0"  <?=(!$this->originalInput('Letter_Btn', $this->get('Letter_Btn')))?'checked="checked"':'' ?> />
                <label for="Letter_Btn-no" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>

    <div class="form-group <?=$this->validation()->hasError('Color') ? 'has-error' : '' ?>">
        <label for="Color" class="col-lg-2 control-label">
            <?=$this->getTrans('Color') ?>:
        </label>
        <div class="col-lg-4">
            <input type="Color"
                   class="form-control"
                   id="Color"
                   name="Color"
                   min="1"
                   value="<?=$this->escape($this->originalInput('Color', $this->get('Color'))) ?>"
                   required />
        </div>
    </div>
    <?=$this->getSaveBar() ?>
</form>
