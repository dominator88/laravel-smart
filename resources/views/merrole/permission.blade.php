
<ul class="permission">
    <?php foreach( $funcData as $row ) :?>
    <li class="func-tree">
        <div class="func-node" data-func-id="<?= $row['id']?>">
            <span class="func" data-id="<?= $row['id']?>"><?= $row['name'] ?></span>
            <span class="func-opt-row" >
        <?php foreach( $row['privilege'] as $p ) :?>
                <span class="func-opt" data-id="<?= $p['id']?>"><i class="fa fa-square-o"></i> <?= $privilegeName[$p['name']] ?></span>
                <?php endforeach ; ?>
      </span>
        </div>
        <?php if ( isset( $row['children'] ) ) :?>
        <ul class="sub-permission">
            <?php foreach( $row['children'] as $children ) :?>
            <li class="func-tree">
                <div class="func-node" data-func-id="<?= $children['id']?>">
                    <span class="func" data-id="<?= $children['id']?>"><?= $children['name'] ?></span>
                    <span class="func-opt-row">
            <?php foreach( $children['privilege'] as $p ) :?>
                        <span class="func-opt" data-id="<?= $p['id']?>"><i class="fa fa-square-o"></i> <?= $privilegeName[$p['name']] ?></span>
                        <?php endforeach ; ?>
          </span>
                </div>
                <?php if ( isset( $children['children'] ) ) :?>
                <ul class="sub-permission">
                    <?php foreach( $children['children'] as $row ) :?>
                    <li class="func-tree">
                        <div class="func-node" data-func-id="<?= $row['id']?>">
                            <span class="func" data-id="<?= $row['id']?>"><?= $row['name'] ?></span>
                            <span class="func-opt-row">
                <?php foreach( $row['privilege'] as $p) :?>
                                <span class="func-opt" data-id="<?= $p['id']?>"><i class="fa fa-square-o"></i> <?= $privilegeName[$p['name']] ?></span>
                                <?php endforeach ; ?>
              </span>
                        </div>
                    </li>
                    <?php endforeach ; ?>
                </ul>
                <?php endif ;?>
            </li>
            <?php endforeach ; ?>
        </ul>
        <?php endif ; ?>
    </li>
    <?php endforeach ; ?>
</ul>
