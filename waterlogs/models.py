from django.db import models
from django.forms import ModelForm
from aquariums.models import Aquarium
#from aquaridawg.fields import EnumField

# Models
class WaterLog(models.Model):
    waterLogID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    testedOn = models.DateTimeField(verbose_name='Tested On',editable=True,blank=False)
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)    
    ammonia = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrites = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrates = models.DecimalField(max_digits=5,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(verbose_name='pH',max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(null=True,blank=True)
    amountExchanged = models.PositiveSmallIntegerField(verbose_name='Water Exchanged',null=True,blank=True)
    comments = models.TextField(blank=True)
    def __unicode__(self):
        return unicode(self.testedOn)
    class Meta:
        db_table = 'WaterLog'
        verbose_name = 'Water Log'
        verbose_name_plural = 'Water Logs'

# Model Forms
class WaterLogForm(ModelForm):
    class Meta:
        model = WaterLog
        exclude = ('aquariumID')
