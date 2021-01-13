<div number-field <?= $this->expandAttributes($this->options) ?>>

    <!-- label -->
    <?php if ($this->label != ""): ?>
        <label <?= $this->expandAttributes($this->labelOptions) ?>
            class="<?= $this->labelClass ?>" for="<?= $this->renderID; ?>">
                <?= $this->label ?> <?php if ($this->isRequired()) : ?> <div class="required">*</div> <?php endif; ?>
        </label>
    <?php endif; ?>
    <!-- /label -->

    <div class="<?= $this->fieldColClass ?>">

        <!-- data -->
        <data name="name" class="hide"><?= $this->name ?></data>
        <data name="value" class="hide"><?= $this->value ?></data>
        <data name="valueshow" class="hide"><?= $this->valueshow ?></data>
        <data name="usecommas" class="hide"><?= $this->usecommas ?></data>
        <data name="minValue" class="hide"><?= $this->minValue ?></data>
        <data name="maxValue" class="hide"><?= $this->maxValue ?></data>
        <data name="ac_mode" class="hide"><?= $this->acMode ?></data>
        <data name="model_class" class="hide"><?= Helper::getAlias($model) ?></data>
        <data name="rel_model_class" class="hide"><?= $this->modelClass ?></data>
        <data name="params" class="hide"><?= json_encode($this->params) ?></data>
        <data name="list" class="hide"><?= json_encode($this->acList) ?></data>
        <data name="ng_disabled" class="hide"><?= $this->isDisabled(); ?></data>
        <!-- /data -->

        <!-- field -->
        <?php if ($this->prefix != "" || $this->postfix != ""): ?>
            <div class="input-group">
                <!-- prefix -->
                <?php if ($this->prefix != ""): ?>
                    <span class="input-group-addon"
                    style="{{ numberFieldDisabled ? 'background:#fff;border:1px solid #ececeb;border-right:0px' : '' }}">
                    <?= $this->prefix ?>
                    </span>
                <?php endif; ?>

                <!-- value -->
                <input type="<?= $this->fieldType ?>" <?= $this->expandAttributes($this->fieldOptions) ?>
                       ng-model="valueshow" ng-focus="tfFocus()" ng-blur="tfBlur()" ng-change="update()" value="<?= $this->valueshow ?>"
                       />
                <input type="<?= $this->fieldType ?>" <?= $this->expandAttributes($this->fieldOptions) ?>
                       ng-model="value" value="<?= $this->value ?>"
                       style="display: none;"
                       />

                <!-- postfix -->
                <?php if ($this->postfix != ""): ?>
                    <span class="input-group-addon"
                    style="{{ numberFieldDisabled ? 'background:#fff;border:1px solid #ececeb;border-left:0px' : '' }}">
                    <?= $this->postfix ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- value -->
            <input type="<?= $this->fieldType ?>" <?= $this->expandAttributes($this->fieldOptions) ?>
                   ng-model="valueshow" ng-focus="tfFocus()" ng-blur="tfBlur()" ng-change="update()" value="<?= $this->valueshow ?>"/>
            <input type="<?= $this->fieldType ?>" <?= $this->expandAttributes($this->fieldOptions) ?>
				   ng-model="value" value="<?= $this->value ?>"
				   style="display: none;"
                   />

        <?php endif; ?>
        <!-- /field -->

        <!-- error -->
        <div ng-if="errors[name]" class="alert error alert-danger">
            {{ errors[name][0]}}
        </div>
        <!-- /error -->
    </div>
</div>