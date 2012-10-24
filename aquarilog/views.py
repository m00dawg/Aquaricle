from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.http import HttpResponseRedirect, HttpResponse
from django.core.urlresolvers import reverse
from aquarilog.models import Aquarium, WaterLog

def index(request):
    aquarium_list = Aquarium.objects.all().order_by('-activeSince')
    return render(request,
        'aquarilog/index.html',
        {'aquarium_list': aquarium_list}
    )

def aquarium_details(request, aquarium_id, error_message=None):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    latest_water_logs = \
        WaterLog.objects.filter(aquariumID = aquarium_id) \
            .order_by('testedOn')[:10]
    return render(request,
        'aquarilog/aquarium.html',
        {'aquarium': aquarium,
         'latest_water_logs': latest_water_logs,
         'error_message': error_message}
    )
    
def add_water_sample(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    log = WaterLog(aquariumID = aquarium, testedOn = timezone.now())
    try:
        if request.POST['temperature']:
            if float(request.POST['temperature']) < 0:
                raise Exception('Temperature Cannot Be 0')
            log.temperature = request.POST['temperature']
    except (Exception as e, WaterLog.DoesNotExist):
        return aquarium_details(request, aquarium_id, error_message=e.value)

    return HttpResponseRedirect(reverse('aquarilog.views.aquarium', args=(aquarium.aquariumID,)))
'''
    if request.POST['ammonia']:
        log.ammonia = request.POST['ammonia']
    if request.POST['nitrites']:
        log.nitrites = request.POST['nitrite']
    if request.POST['nitrates']:
        log.nitrates = request.POST['nitrate']
    if request.POST['pH']:
        log.pH = request.POST['pH']
    if request.POST['KH']:
        log.KH = request.POST['KH']
    if request.POST['ammountExchanged']:
        log.ammountExchanged = request.POST['ammountExchanged']
    if request.POST['comments']:
        log.comments = request.POST['comments']
    log.save
'''
    
