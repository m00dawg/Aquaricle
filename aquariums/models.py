from django.db import models
from waterprofiles.models import WaterProfile

class Aquarium(models.Model):
    aquariumID = models.AutoField(primary_key=True)
    waterProfileID = models.ForeignKey(WaterProfile,verbose_name='Water Profile',db_column='waterProfileID')
    activeSince = models.DateTimeField(verbose_name='Active Since',editable=True,null=True,blank=True)
    size = models.PositiveSmallIntegerField(verbose_name='Size (Liter)',null=False,blank=False)
    name = models.CharField(max_length=24,unique=True)
    location = models.CharField(max_length=24,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'Aquariums'