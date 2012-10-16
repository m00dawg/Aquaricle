from django.db import models

class WaterProfile(models.Model):
	WATER_TYPE_CHOICES = (
		(1, 'Freshwater'),
		(2, 'Saltwater'),
		(3, 'Other')
	)
	waterProfileID = models.AutoField(primary_key=True)
	waterType = models.PositiveSmallIntegerField(choices=WATER_TYPE_CHOICES,null=False,blank=False)
	name = models.CharField(max_length=24,unique=True)
	temperature = models.PositiveSmallIntegerField(null=True,blank=True)
	pH = models.DecimalField(max_digits=3,decimal_places=1,null=True,blank=True)
	KH = models.PositiveSmallIntegerField(null=True,blank=True)
	def __unicode__(self):
		return self.name
	class Meta:
		db_table = 'WaterProfiles'	

class Aquarium(models.Model):
	aquariumID = models.AutoField(primary_key=True)
	waterProfileID = models.ForeignKey(WaterProfile,verbose_name='Water Profile',db_column='waterProfileID')
	activeSince = models.DateTimeField(verbose_name='Active Since',editable=True,null=True,blank=True)
	size = models.PositiveSmallIntegerField(null=False,blank=False)
	name = models.CharField(max_length=24,unique=True)
	location = models.CharField(max_length=24,blank=True)
	def __unicode__(self):
		return self.name
	class Meta:
		db_table = 'Aquariums'

class WaterLog(models.Model):
	waterLogID = models.AutoField(primary_key=True)
	aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
	testedOn = models.DateTimeField(verbose_name='Tested On',editable=True,blank=False)
	temperature = models.PositiveSmallIntegerField(null=True,blank=True)
	ammonia = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
	nitrites = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
	nitrates = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
	pH = models.DecimalField(max_digits=3,decimal_places=1,null=True,blank=True)
	KH = models.PositiveSmallIntegerField(null=True,blank=True)
	amountExchanged = models.PositiveSmallIntegerField(null=True,blank=True)
	comments = models.TextField(blank=True)
	def __unicode__(self):
		return unicode(self.testedOn)
	class Meta:
		db_table = 'WaterLog'
