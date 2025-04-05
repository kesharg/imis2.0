<!-- Last Modified Date: 18-04-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
<!-- Main node for this component -->
<div class="timeline">
    @foreach($revisions as $date=>$histories)
    <!-- Timeline time label -->
    <div class="time-label">
        <span class="bg-green">{{ $date }}</span>
    </div>
    @foreach($histories as $history)
            <div>
                <!-- Before each timeline item corresponds to one icon on the left scale -->
                <i class="fas fa-pen bg-blue"></i>
                <!-- Timeline item -->
                <div class="timeline-item">
                    <!-- Time -->
                    <span class="time"><i class="fas fa-clock"></i>{{ $history->created_at->format('H:i') }}</span>
                    <!-- Header. Optional -->
                    @if($history->key == 'created_at' && !$history->old_value)
                        @if($history->userResponsible())
                            <h3 class="timeline-header">{{ $history->userResponsible()->name }} created this resource.</h3>
                        @endif
                    @else
                        @if($history->userResponsible())
                            <h3 class="timeline-header">{{ $history->userResponsible()->name }} changed {{ $history->fieldName() }} from {{ $history->oldValue()??"null" }} to {{ $history->newValue() }}</h3>
                        @endif
                    @endif
                    {{--<!-- Body -->
                    <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                    </div>
                    <!-- Placement of additional controls. Optional -->
                    <div class="timeline-footer">
                        <a class="btn btn-primary btn-sm">Read more</a>
                        <a class="btn btn-danger btn-sm">Delete</a>
                    </div>--}}
                </div>
            </div>
    @endforeach
    @endforeach
    <!-- The last icon means the story is complete -->
    <div>
        <i class="fas fa-clock bg-gray"></i>
    </div>
</div>
