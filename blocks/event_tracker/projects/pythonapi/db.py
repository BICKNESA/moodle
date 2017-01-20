from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, scoped_session
from sqlalchemy.ext.declarative import declarative_base

engine = create_engine("mysql+mysqlconnector://movie:password@localhost/movie")

DBSession = scoped_session(sessionmaker())
DBSession.configure(bind=engine)

Model = declarative_base()
