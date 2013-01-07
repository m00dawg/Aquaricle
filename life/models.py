from django.db import models
from django.utils import timezone

from aquariums.models import Aquarium

class AquariumLife(models.Model):
    aquaLifeID = models.AutoField(primary_key=True)
    lifeID = models.ForeignKey(Life,verbose_name='Species',db_column='lifeID')
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')


    name = models.CharField(max_length=64,blank=True,null=True)
    dateAdded = models.DateTimeField(
        verbose_name='Date Added',
        editable=True,
        blank=False,
        default=timezone.datetime.now())
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'AquariumLife'
        unique_together = ('aquariumID', 'name')
        
class Life(models.Model):
    lifeID = models.AutoField(primary_key=True)
    commonName = models.CharField(max_length=64,blank=True,null=True)
    kingdom = models.CharField(max_length=64,blank=True,null=True)