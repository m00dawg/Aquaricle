from django.shortcuts import render, get_object_or_404
from life.models import Life
from life.models import LifeLog

def life_details(request, life_id):
    life = get_object_or_404(Life, pk=life_id)
    lifeLog = LifeLog.objects.filter(lifeID = life_id).order_by('logDate')
    return render(request,
        'life_details.html',
        {'life' : life,
         'lifeLog' : lifeLog,}
    )