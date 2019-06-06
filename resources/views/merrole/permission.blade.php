
<ul class="permission">
    @foreach($funcData as $row)
    <li class="func-tree">
        <div class="func-node" data-func-id="{{ $row->id }}">
            <span class="func" data-id="{{ $row->id }}">{{ $row->name }}</span>
            <span class="func-opt-row" >
       <!--权限节点-->
            @if($row->node->count() > 0)
                @foreach($row->node as $node)
                 
                <span class="func-opt" data-id="{{ $node->id }}"><i class="fa fa-square-o"></i> 
                   
                {{ $node->name }}  
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
           @if($children->node->count() > 0)
                @foreach($children->node as $node)
               
                <span class="func-opt" data-id="{{ $node->id }}"><i class="fa fa-square-o"></i> {{ $node->name }}</span>
        
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
                @foreach($cchildren->node as $node)
               
                <span class="func-opt" data-id="{{ $node->id }}"><i class="fa fa-square-o"></i> {{ $node->name }}</span>
   
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
