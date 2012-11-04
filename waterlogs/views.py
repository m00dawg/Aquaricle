from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.http import HttpResponseRedirect, HttpResponse
from django.core.urlresolvers import reverse
from django.core.exceptions import ObjectDoesNotExist

from aquariums.models import Aquarium
from waterlogs.models import WaterLog, WaterLogForm

# Add success_url somehow?
# def waterlog_form (request, form=_class='someForm', template_name='template.html', success_url='/foo')

def waterlog_entry(request, waterlog_id):
    waterlog = get_object_or_404(WaterLog, pk=waterlog_id)
    
    # Process Water Log Entries
    if request.method == 'POST':
        waterlog_form = WaterLogForm(request.POST, instance=waterlog)
        if waterlog_form.is_valid():
            waterlog_form.save()
            return HttpResponseRedirect(reverse(
                'waterlogs.views.waterlog_entry', 
                args=(waterlog_id,)))
    else:
        waterlog_form = WaterLogForm(instance=waterlog)

    return render(request,
        'waterlog_entry.html',
        {'waterlog' : waterlog,
         'waterlog_form': waterlog_form,}
    )
    
def add_sample(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    waterlog = WaterLog(aquariumID = aquarium)
    waterlog_form = WaterLogForm(instance=waterlog)

    # Process Water Log Entries
    if request.method == 'POST':
        waterlog_form = WaterLogForm(request.POST, instance=waterlog)
        if waterlog_form.is_valid():
            new_sample = waterlog_form.save()
            return HttpResponseRedirect(reverse('aquariums.views.aquarium_details', args=(aquarium_id,)))

    return render(request,
        'add_sample.html',
        {'aquarium_id' : aquarium_id,
         'waterlog' : waterlog,
         'waterlog_form': waterlog_form,}
    )
