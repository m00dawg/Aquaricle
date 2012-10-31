from django.db import models

# Contributed Code Found From http://stackoverflow.com/questions/21454/specifying-a-mysql-enum-in-a-django-model
class EnumField(models.Field):
    """
    A field class that maps to MySQL's ENUM type.

    Usage:

    Class Card(models.Model):
        suit = EnumField(values=('Clubs', 'Diamonds', 'Spades', 'Hearts'))

    c = Card()
    c.suit = 'Clubs'
    c.save()
    """
    def __init__(self, *args, **kwargs):
        self.values = kwargs.pop('values')
        kwargs['choices'] = [(v, v) for v in self.values]
        kwargs['default'] = self.values[0]
        super(EnumField, self).__init__(*args, **kwargs)

    def db_type(self):
        return "enum({0})".format( ','.join("'%s'" % v for v in self.values) )


