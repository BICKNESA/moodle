from sqlalchemy import Column, ForeignKey, UniqueConstraint
from sqlalchemy.types import Integer, String
from sqlalchemy.orm import relationship

from db import Model

class Movie(Model):
    __tablename__ = 'movies'
    id = Column(Integer, primary_key=True)
    name = Column(String(100), nullable=False, unique=True)
    length = Column(Integer)
    category_id = Column(Integer, ForeignKey('categories.id', name='category_fk'))
    category = relationship('Category')

    def __str__(self):
        return self.name

    def as_dict(self):
        return {
            'id': self.id,
            'name': self.name,
            'length': self.length,
            # 'category_id': self.category_id
        }


class Person(Model):
    __tablename__ = 'people'
    id = Column(Integer, primary_key=True)
    name = Column(String(100), nullable=False, unique=True)

    def __str__(self):
        return self.name

    def as_dict(self):
        return {
            'id': self.id,
            'name': self.name
        }


class Vote(Model):
    __tablename__ = 'votes'
    id = Column(Integer, primary_key=True)
    person_id = Column(Integer, ForeignKey('people.id', name='person_fk'), nullable=False)
    movie_id = Column(Integer, ForeignKey('movies.id', name='movie_fk'), nullable=False)
    person = relationship('Person')
    movie = relationship('Movie')

    # a unique constraint across multiple colums
    __table_args__ = (
        UniqueConstraint('person_id', 'movie_id', name='votes_once'),
    )

    def as_dict(self):
        return {
            'id': self.id,
            'person': self.person.as_dict(),
            'movie': self.movie.as_dict()
        }


class Category(Model):
    __tablename__ = 'categories'
    id = Column(Integer, primary_key=True)
    name = Column(String(30), nullable=False, unique=True)
