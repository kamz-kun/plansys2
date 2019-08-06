<div class="thead">
   <?php echo $this->generateHeaders('class'); ?>
   <?php $cols = $this->getFreezedCols(); ?>
</div>

<div class="pane pane--table1">
  <div class="pane-hScroll" <?php 
                                 if($this->hScroll != 0){
                                  echo 'style="max-width: '.$this->hScroll.'px"';
                                 } else {
                                  echo 'style="max-width: 100%"';
                                 }
                                    ?>>

    <table>
    <?php echo $this->generateHeaders('tag', $cols); ?>
    </table>
    
    <div class="pane-vScroll" <?php 
                                 if($this->vScroll != 0 && $this->hScroll == 0){
                                  echo 'style="max-height: '.$this->vScroll.'px; width: 100%;"';
                                 } else if($this->vScroll != 0 && $this->hScroll != 0){
                                  echo 'style="max-height: '.$this->vScroll.'px;"';
                                 }
                                    ?>>
      <table>
        <tbody>
          <tr ng-repeat-start="row in datasource.data track by $index" ng-if="row.$type=='g'" lv="{{row.$level}}" class="g">
            <?php foreach ($this->columns as $idx => $col): ?>
                <?= $this->getGroupTemplate($col, $idx); ?>
            <?php endforeach; ?>
          </tr>
          <tr ng-repeat-end ng-if="!row.$type || row.$type == 'r' || (row.$type == 'a' && row.$aggr)" lv="{{row.$level}}" class="{{!!row.$type ? row.$type : 'r'}} {{rowStateClass(row)}}">
            <?php foreach ($this->columns as $idx => $col): ?>
                <?= $this->getRowTemplate($col, $idx); ?>
            <?php endforeach; ?>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/template" name="columnsnew"><?= json_encode($this->columns); ?></script>