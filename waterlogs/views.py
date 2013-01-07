from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.http import HttpResponseRedirect, HttpResponse
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.core.urlresolvers import reverse
from django.core.exceptions import ObjectDoesNotExist

from aquariums.models import Aquarium
from waterlogs.models import WaterLog, WaterLogForm

# Add success_url somehow?
# def waterlog_form (request, form=_class='someForm', template_name='template.html', success_url='/foo')

def waterlog_entries(request, aquarium_id, page=1, limit=20):
    """ 
        Retrieve paginated log entries for a particular aquarium paginating by date
    """
    # index (items X through Y)
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    entries = WaterLog.objects.filter(aquariumID = aquarium_id) \
        .order_by('-testedOn')[0:limit]
    lastChange = WaterLog.stats.get_last_water_change(aquarium_id)
    
    return render(request,
                  'waterlog_entries.html',
                  {'aquarium': aquarium,
                   'lastChange' : lastChange,
                   'entries' : entries,})

def waterlog_entry(request, waterlog_id):
    """ Return a single water log entry for viewing and editing"""
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
    waterlog.testedOn = timezone.datetime.now()
    waterlog_form = WaterLogForm(instance=waterlog)

    # Process Water Log Entries
    if request.method == 'POST':
        waterlog_form = WaterLogForm(request.POST, instance=waterlog)
        if waterlog_form.is_valid():
            waterlog_form.save()   
            return HttpResponseRedirect(reverse('aquariums.views.aquarium_details', args=(aquarium_id,)))

    return render(request,
        'add_sample.html',
        {'aquarium_id' : aquarium_id,
         'waterlog' : waterlog,
         'waterlog_form': waterlog_form,}
    )
