<div <?= $this->expandAttributes($this->options) ?>>
    <div class="column-field-row">
        <?php for ($i = 1; $i <= $this->totalColumns; $i++): ?> 
            <div class="column-field-cell" style="width:<?= $this->{'w' . $i} ?>;
                <?= $this->showBorder == 'Yes' && $i != 1 ? "border-left:1px solid #ececeb;" : "" ?>
                <?= $this->showBorder == 'Yes' && $i == 1 ? "border-right:1px solid #ececeb;" : "" ?>
                ">
                <div class="column-field-inner" <?= $this->expandAttributes($this->perColumnOptions) ?>>
                    <?= $this->renderColumn($i); ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>