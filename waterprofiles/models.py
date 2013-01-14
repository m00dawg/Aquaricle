from django.db import models

# Create your models here.
class WaterProfile(models.Model):
    waterProfileID = models.AutoField(primary_key=True)
#    waterType = EnumField(values=('Freshwater', 'Saltwater', 'Other'),null=True,blank=True)
#    waterType = models.PositiveSmallIntegerField(null=True,blank=True)
    waterType = models.CharField(verbose_name='Water Type',max_length='10',null=False,blank=False) 
    name = models.CharField(max_length=24,unique=True)
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(verbose_name='KH (Degrees)',null=True,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'WaterProfiles'  
        verbose_name = 'Water Profile'
        verbose_name_plural = 'Water Profiles'
