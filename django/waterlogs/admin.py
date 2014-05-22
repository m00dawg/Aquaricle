from waterlogs.models import WaterAdditive,WaterLog
from django.contrib import admin

class WaterLogAdmin(admin.ModelAdmin):
	list_display = ('aquariumID', 'testedOn', 'temperature', 'ammonia', 'nitrites', 
		'nitrates', 'pH', 'KH', 'amountExchanged', 'waterAdditiveID', 'additiveAmount')

admin.site.register(WaterAdditive)
admin.site.register(WaterLog, WaterLogAdmin)