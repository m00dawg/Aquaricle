from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect, HttpResponse

from aquariums.models import Aquarium
from life.models import Life, LifeLog, LifeForm, LifeLogForm

def life_details(request, life_id):
    life = get_object_or_404(Life, pk=life_id)
    life_logs = LifeLog.objects.filter(lifeID = life_id).order_by('logDate')
    life_log_entry = LifeLog(lifeID = life)
    life_log_form = LifeLogForm(instance=life_log_entry)
    
    # Process Life Log Entries
    if request.method == 'POST':
        life_log_form = LifeLogForm(request.POST, instance=life_log_entry)
        if life_log_form.is_valid():
            new_life_log = life_log_form.save()
            return HttpResponseRedirect(
                reverse('life.views.life_details', 
                args=(life_id,)))

    return render(request,
        'life_details.html',
        {'life' : life,
         'life_logs' : life_logs,
         'life_log_form' : life_log_form,}
    )
    
def add_life(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    life = Life(aquariumID = aquarium)
    life.dateAdded = timezone.datetime.now()
    life_form = LifeForm(instance=life)
    return render(request,
        'add_life.html', 
        {'aquarium_id' : aquarium_id,
         'life' : life,
         'life_form' : life_form}
    )