id: {{ $petInfo['id'] }} </br>
category:</br>
    id: {{ $petInfo['category']['id'] }} </br>
    name: {{ $petInfo['category']['name'] }} </br>
name: {{ $petInfo['name'] }} </br>
photoUrls: </br>
    @foreach($petInfo['photoUrls'] as $element)
        {{ $element }}</br>
    @endforeach
tags: </br>
    @foreach($petInfo['tags'] as $element)
        id: {{ $element['id'] }}</br>
        name: {{ $element['name'] }}</br>
    @endforeach
status: {{ $petInfo['status'] }} </br>
