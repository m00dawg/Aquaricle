from django.shortcuts import render_to_response
from django.shortcuts import get_object_or_404
from aquarilog.models import Aquarium
#from django.template import Context, loader
#from django.http import HttpResponse


def index(request):
    aquarium_list = Aquarium.objects.all().order_by('-activeSince')
    return render_to_response(
        'aquarilog/index.html',
        {'aquarium_list': aquarium_list}
    )

def aquarium(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    return render_to_response(
        'aquarilog/aquarium.html',
        {'aquarium': aquarium}
    )

#    output =  ', '.join([a.name for a in aquarium_list])
#    template = loader.get_template('aquarilog/index.html')
#    context = Context({
#        'aquarium_list' : aquarium_list
#    })
#    return HttpResponse(template.render(context))
