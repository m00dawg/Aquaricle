from django.db import models
from waterprofiles.models import WaterProfile
class Aquarium(models.Model):
    aquariumID = models.AutoField(primary_key=True)
    waterProfileID = models.ForeignKey(WaterProfile,verbose_name='Water Profile',db_column='waterProfileID')
    activeSince = models.DateTimeField(verbose_name='Active Since',editable=True,null=True,blank=True)
    measurementUnits = models.CharField(max_length=8,
        blank=False,
        null=False,
        choices=[('Metric', 'Metric'), ('Imperial', 'Imperial')]
        )    
    capacity = models.DecimalField(verbose_name='Capacity', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    length = models.DecimalField(verbose_name='Length', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    width = models.DecimalField(verbose_name='Width', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    height = models.DecimalField(verbose_name='Height', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    name = models.CharField(max_length=24,unique=True)
    location = models.CharField(max_length=24,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'Aquariums'