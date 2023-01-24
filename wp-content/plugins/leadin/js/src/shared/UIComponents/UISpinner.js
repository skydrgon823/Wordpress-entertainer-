import React from 'react';
import { styled } from '@linaria/react';
import { css } from '@linaria/core';
import { CALYPSO_MEDIUM, CALYPSO } from './colors';

const SpinnerOuter = styled.div`
  align-items: center;
  color: #00a4bd;
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 100%;
  height: 100%;
  margin: '2px';
`;

const SpinnerInner = styled.div`
  align-items: center;
  display: flex;
  justify-content: center;
  width: 100%;
  height: 100%;
`;

const Circle = styled.circle`
  fill: none;
  stroke: ${props => props.color};
  stroke-width: 5;
  stroke-linecap: round;
  transform-origin: center;
`;

const animated = css`
  animation: dashAnimation 2s ease-in-out infinite,
    spinAnimation 2s linear infinite;

  @keyframes dashAnimation {
    0% {
      stroke-dasharray: 1, 150;
      stroke-dashoffset: 0;
    }

    50% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -50;
    }

    100% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -140;
    }
  }

  @keyframes spinAnimation {
    transform: rotate(360deg);
  }
`;

export default function UISpinner({ size = 20 }) {
  return (
    <SpinnerOuter>
      <SpinnerInner>
        <svg height={size} width={size} viewBox="0 0 50 50">
          <Circle color={CALYPSO_MEDIUM} cx="25" cy="25" r="22.5" />
          <Circle
            className={animated}
            color={CALYPSO}
            cx="25"
            cy="25"
            r="22.5"
          />
        </svg>
      </SpinnerInner>
    </SpinnerOuter>
  );
}
