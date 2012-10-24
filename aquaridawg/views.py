from django.shortcuts import render_to_response
from django.shortcuts import get_object_or_404
from aquarilog.models import Aquarium, WaterLog
#from django.template import Context, loader
#from django.http import HttpResponse


def index(request):
    return render_to_response(
        'index.html',
    )
    
'''
def aquarium(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    latest_water_logs = 
        WaterLog.objects
            .filter(aquariumID = aquarium_id)
            .order_by('-testedOn')[:10]
    return render_to_response(
        'aquarilog/aquarium.html',
        {'aquarium': aquarium},
        {'latest_water_logs', latest_water_logs}
    )
'''
#    output =  ', '.join([a.name for a in aquarium_list])
#    template = loader.get_template('aquarilog/index.html')
#    context = Context({
#        'aquarium_list' : aquarium_list
#    })
#    return HttpResponse(template.render(context))
