
<ul class="permission">
    @foreach($funcData as $row)
    <li class="func-tree">
        <div class="func-node" data-func-id="{{ $row->id }}">
            <span class="func" data-id="{{ $row->id }}">{{ $row->name }}</span>
            <span class="func-opt-row" >
       <!--权限节点-->
            @if($row->privilege->count() > 0)
                @foreach($row->privilege as $node)
                 
                <span class="func-opt" data-id="{{ $node->node_id }}"><i class="fa fa-square-o"></i> 
                   
                {{ $node->node->name }}  
                </span>
                @endforeach
            @endif
      </span>

        </div>
        @if($row->children->count() > 0)
        <ul class="sub-permission">
            @foreach($row->children as $children)
            <li class="func-tree">
                <div class="func-node" data-func-id="{{ $children->id }}">
                    <span class="func" data-id="{{ $children->id }}">{{ $children->name }}</span>
                    <span class="func-opt-row">
           @if($children->privilege->count() > 0)
                @foreach($children->privilege as $node)
               
                <span class="func-opt" data-id="{{ $node->node_id }}"><i class="fa fa-square-o"></i> {{ $node->node->name }}</span>
        
                @endforeach
            @endif
          </span>
                </div>
                @if($children->children->count() > 0)
                <ul class="sub-permission">
                    @foreach($children->children as $cchildren)
                    <li class="func-tree">
                        <div class="func-node" data-func-id="{{ $cchildren->id }}">
                            <span class="func" data-id="{{ $cchildren->id }}">{{ $cchildren->name }}</span>
                            <span class="func-opt-row">
                @if($cchildren->privilege->count() > 0)
                @foreach($cchildren->privilege as $node)
               
                <span class="func-opt" data-id="{{ $node->node_id }}"><i class="fa fa-square-o"></i> {{ $node->node->name }}</span>
   
                @endforeach
            @endif
              </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
       @endif
    </li>
    @endforeach
</ul>
