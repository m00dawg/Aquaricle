from aquarilog.models import Aquarium
from aquarilog.models import WaterLog
from django.contrib import admin

admin.site.register(Aquarium)

class WaterLogAdmin(admin.ModelAdmin):
	list_display = ('testedOn', 'temperature', 'ammonia', 'nitrites', 
		'nitrates', 'pH', 'KH', 'amountExchanged')

admin.site.register(WaterLog, WaterLogAdmin)
