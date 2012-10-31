from waterlogs.models import WaterLog
from django.contrib import admin

class WaterLogAdmin(admin.ModelAdmin):
	list_display = ('aquariumID', 'testedOn', 'temperature', 'ammonia', 'nitrites', 
		'nitrates', 'pH', 'KH', 'amountExchanged')

admin.site.register(WaterLog, WaterLogAdmin)