from django.db import models
from django.forms import ModelForm
from django.utils import timezone
from aquariums.models import Aquarium
from waterlogs.managers import WaterLogStatsManager
#from aquaridawg.fields import EnumField

class WaterAdditive(models.Model):
    waterAdditiveID = models.AutoField(primary_key=True)
    name = models.CharField(max_length=32,unique=True)
    #additiveInterval = models.PositiveSmallIntegerField(verbose_name='Additive Interval (Days)',null=True,blank=True)   
    def __unicode__(self):
        return unicode(self.name)
    class Meta:
        db_table = 'WaterAdditives'
        verbose_name = 'Water Additive'
        verbose_name_plural = 'Water Additives'

class WaterLog(models.Model):
    waterLogID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    waterAdditiveID = models.ForeignKey(WaterAdditive,verbose_name="Additive",db_column='waterAdditiveID',null=True,blank=True)
    additiveAmount = models.PositiveSmallIntegerField(verbose_name='Additive Amount (mL)',null=True,blank=True)
    testedOn = models.DateTimeField(
        verbose_name='Tested On',
        editable=True,
        blank=False,
        null=False,
        default=timezone.datetime.now())
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)    
    ammonia = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrites = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrates = models.DecimalField(max_digits=5,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(verbose_name='pH',max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(null=True,blank=True)
    amountExchanged = models.PositiveSmallIntegerField(verbose_name='Liters Exchanged',null=True,blank=True)
    comments = models.TextField(blank=True)
    def __unicode__(self):
        return unicode(self.testedOn)
    class Meta:
        db_table = 'WaterLogs'
        verbose_name = 'Water Log'
        verbose_name_plural = 'Water Logs'
    objects = models.Manager()
    stats = WaterLogStatsManager();


# Model Forms
class WaterLogForm(ModelForm):
    class Meta:
        model = WaterLog
        fields = ('testedOn', 'waterAdditiveID', 'additiveAmount', 'amountExchanged', 'ammonia', 'nitrites', 'nitrates', 'pH', 'KH', 'comments')
        exclude = ('aquariumID')
